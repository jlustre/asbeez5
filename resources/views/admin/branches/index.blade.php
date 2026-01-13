@extends('admin.layout')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
    <h2>Branches</h2>
    <a class="btn" href="{{ route('admin.branches.create') }}">Create Branch</a>
</div>
@if(session('status'))
<div class="muted" style="margin-bottom:12px;">{{ session('status') }}</div>
@endif
<table style="width:100%; border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:left; padding:8px;">Name</th>
            <th style="text-align:left; padding:8px;">Code</th>
            <th style="text-align:left; padding:8px;">Category</th>
            <th style="text-align:left; padding:8px;">City</th>
            <th style="text-align:left; padding:8px;">Active</th>
            <th style="text-align:left; padding:8px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($branches as $b)
        <tr style="border-top:1px solid #1f2937;">
            <td style="padding:8px;">{{ $b->name }}</td>
            <td style="padding:8px;" class="muted">{{ $b->code }}</td>
            <td style="padding:8px;">{{ optional($b->category)->name }}</td>
            <td style="padding:8px;">{{ $b->city }}</td>
            <td style="padding:8px;">{{ $b->is_active ? 'Yes' : 'No' }}</td>
            <td style="padding:8px;">
                <a class="btn" href="{{ route('admin.branches.edit', $b) }}">Edit</a>
                <form action="{{ route('admin.branches.destroy', $b) }}" method="POST"
                    style="display:inline-block; margin-left:8px;">
                    @csrf @method('DELETE')
                    <button class="btn" onclick="return confirm('Delete this branch?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div style="margin-top:12px;">{{ $branches->links() }}</div>
@endsection