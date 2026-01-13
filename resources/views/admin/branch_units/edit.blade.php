@extends('admin.layout')

@section('content')
<h2 style="margin-bottom:16px;">Edit Branch Unit</h2>
<form action="{{ route('admin.branch-units.update', $branch_unit) }}" method="POST" style="max-width:800px;">
    @csrf @method('PUT')
    <div style="margin-bottom:12px;">
        <label>Branch</label>
        <select name="branch_id" style="width:100%; padding:8px;">
            @foreach($branches as $b)
            <option value="{{ $b->id }}" {{ $branch_unit->branch_id == $b->id ? 'selected' : '' }}>{{ $b->name }}
            </option>
            @endforeach
        </select>
        @error('branch_id')<div class="muted">{{ $message }}</div>@enderror
    </div>
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
        <div>
            <label>Unit Number</label>
            <input type="number" name="unit_number" value="{{ old('unit_number', $branch_unit->unit_number) }}" min="1"
                style="width:100%; padding:8px;">
            @error('unit_number')<div class="muted">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Code</label>
            <input type="text" name="code" value="{{ old('code', $branch_unit->code) }}"
                style="width:100%; padding:8px;">
            @error('code')<div class="muted">{{ $message }}</div>@enderror
        </div>
    </div>
    <div style="margin-top:12px;">
        <label>Description</label>
        <input type="text" name="description" value="{{ old('description', $branch_unit->description) }}"
            style="width:100%; padding:8px;">
        @error('description')<div class="muted">{{ $message }}</div>@enderror
    </div>
    <button class="btn" type="submit">Update</button>
    <a class="btn" href="{{ route('admin.branch-units.index') }}" style="margin-left:8px;">Cancel</a>
</form>
@endsection