@extends('admin.layout')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
    <h2>Business Categories</h2>
    <a class="btn" href="{{ route('admin.business-categories.create') }}">Create Category</a>
</div>
@if(session('status'))
<div class="muted" style="margin-bottom:12px;">{{ session('status') }}</div>
@endif
<table style="width:100%; border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:left; padding:8px;">Name</th>
            <th style="text-align:left; padding:8px;">Slug</th>
            <th style="text-align:left; padding:8px;">Active</th>
            <th style="text-align:left; padding:8px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $c)
        <tr style="border-top:1px solid #1f2937;">
            <td style="padding:8px;">{{ $c->name }}</td>
            <td style="padding:8px;" class="muted">{{ $c->slug }}</td>
            <td style="padding:8px;">{{ $c->is_active ? 'Yes' : 'No' }}</td>
            <td style="padding:8px;">
                <a class="btn" href="{{ route('admin.business-categories.edit', $c) }}">Edit</a>
                <form action="{{ route('admin.business-categories.destroy', $c) }}" method="POST"
                    style="display:inline-block; margin-left:8px;">
                    @csrf @method('DELETE')
                    <button class="btn" onclick="return confirm('Delete this category?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div style="margin-top:12px;">{{ $categories->links() }}</div>
@endsection