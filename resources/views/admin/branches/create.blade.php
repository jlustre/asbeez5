@extends('admin.layout')

@section('content')
<h2 style="margin-bottom:16px;">Create Branch</h2>
<form action="{{ route('admin.branches.store') }}" method="POST" style="max-width:800px;">
    @csrf
    <div style="margin-bottom:12px;">
        <label>Category</label>
        <select name="business_category_id" style="width:100%; padding:8px;">
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        @error('business_category_id')<div class="muted">{{ $message }}</div>@enderror
    </div>
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
        <div>
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name') }}" style="width:100%; padding:8px;">
            @error('name')<div class="muted">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Code</label>
            <input type="text" name="code" value="{{ old('code') }}" style="width:100%; padding:8px;">
            @error('code')<div class="muted">{{ $message }}</div>@enderror
        </div>
    </div>
    <div style="margin-top:12px;">
        <label>Description</label>
        <textarea name="description" rows="3" style="width:100%; padding:8px;">{{ old('description') }}</textarea>
    </div>
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px; margin-top:12px;">
        <div>
            <label>Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" style="width:100%; padding:8px;">
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" style="width:100%; padding:8px;">
        </div>
    </div>
    <div style="margin-top:12px;">
        <label>Address Line 1</label>
        <input type="text" name="address_line1" value="{{ old('address_line1') }}" style="width:100%; padding:8px;">
    </div>
    <div style="margin-top:12px;">
        <label>Address Line 2</label>
        <input type="text" name="address_line2" value="{{ old('address_line2') }}" style="width:100%; padding:8px;">
    </div>
    <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:12px; margin-top:12px;">
        <div>
            <label>City</label>
            <input type="text" name="city" value="{{ old('city') }}" style="width:100%; padding:8px;">
        </div>
        <div>
            <label>State</label>
            <input type="text" name="state" value="{{ old('state') }}" style="width:100%; padding:8px;">
        </div>
        <div>
            <label>Postal Code</label>
            <input type="text" name="postal_code" value="{{ old('postal_code') }}" style="width:100%; padding:8px;">
        </div>
        <div>
            <label>Country</label>
            <input type="text" name="country" value="{{ old('country') }}" style="width:100%; padding:8px;">
        </div>
    </div>
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px; margin-top:12px;">
        <div>
            <label>Latitude</label>
            <input type="number" step="0.0000001" name="latitude" value="{{ old('latitude') }}"
                style="width:100%; padding:8px;">
        </div>
        <div>
            <label>Longitude</label>
            <input type="number" step="0.0000001" name="longitude" value="{{ old('longitude') }}"
                style="width:100%; padding:8px;">
        </div>
    </div>
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px; margin-top:12px;">
        <div>
            <label>Manager</label>
            <select name="manager_employee_id" style="width:100%; padding:8px;">
                <option value="">-- none --</option>
                @foreach($employees as $e)
                <option value="{{ $e->id }}">{{ $e->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Assistant Manager</label>
            <select name="assistant_manager_employee_id" style="width:100%; padding:8px;">
                <option value="">-- none --</option>
                @foreach($employees as $e)
                <option value="{{ $e->id }}">{{ $e->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px; margin-top:12px;">
        <div>
            <label>Pricing Type</label>
            <input type="text" name="pricing_type" value="{{ old('pricing_type') }}" style="width:100%; padding:8px;">
        </div>
        <div>
            <label>Opening Hours (JSON)</label>
            <textarea name="opening_hours" rows="3"
                style="width:100%; padding:8px;">{{ old('opening_hours') }}</textarea>
        </div>
    </div>
    <div style="margin-top:12px;">
        <label><input type="checkbox" name="is_active" value="1" checked> Active</label>
    </div>
    <button class="btn" type="submit">Save</button>
    <a class="btn" href="{{ route('admin.branches.index') }}" style="margin-left:8px;">Cancel</a>
</form>
@endsection