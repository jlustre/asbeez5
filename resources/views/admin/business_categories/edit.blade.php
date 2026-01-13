@extends('admin.layout')

@section('content')
<h2 style="margin-bottom:16px;">Edit Business Category</h2>
<form action="{{ route('admin.business-categories.update', $category) }}" method="POST" style="max-width:640px;">
    @csrf @method('PUT')
    <div style="margin-bottom:12px;">
        <label>Name</label>
        <input type="text" name="name" value="{{ old('name', $category->name) }}" style="width:100%; padding:8px;">
        @error('name')<div class="muted">{{ $message }}</div>@enderror
    </div>
    <div style="margin-bottom:12px;">
        <label>Slug (optional)</label>
        <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" style="width:100%; padding:8px;">
        @error('slug')<div class="muted">{{ $message }}</div>@enderror
    </div>
    <div style="margin-bottom:12px;">
        <label>Description</label>
        <textarea name="description" rows="3"
            style="width:100%; padding:8px;">{{ old('description', $category->description) }}</textarea>
        @error('description')<div class="muted">{{ $message }}</div>@enderror
    </div>
    <div style="margin-bottom:12px;">
        <label><input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
            Active</label>
    </div>
    <button class="btn" type="submit">Update</button>
    <a class="btn" href="{{ route('admin.business-categories.index') }}" style="margin-left:8px;">Cancel</a>
</form>
@endsection