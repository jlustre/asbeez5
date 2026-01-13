@extends('admin.layout')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
    <h2>Branch Units</h2>
    <a class="btn" href="{{ route('admin.branch-units.create') }}">Create Unit</a>
</div>
@if(session('status'))
<div class="muted" style="margin-bottom:12px;">{{ session('status') }}</div>
@endif
<table style="width:100%; border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:left; padding:8px;">Unit #</th>
            <th style="text-align:left; padding:8px;">Code</th>
            <th style="text-align:left; padding:8px;">Description</th>
            <th style="text-align:left; padding:8px;">Branch</th>
            <th style="text-align:left; padding:8px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($units as $u)
        <tr style="border-top:1px solid #1f2937;">
            <td style="padding:8px;">{{ $u->unit_number }}</td>
            <td style="padding:8px;" class="muted">{{ $u->code }}</td>
            <td style="padding:8px;">{{ $u->description }}</td>
            <td style="padding:8px;">{{ optional($u->branch)->name }}</td>
            <td style="padding:8px;">
                <a class="btn" href="{{ route('admin.branch-units.edit', $u) }}">Edit</a>
                <form action="{{ route('admin.branch-units.destroy', $u) }}" method="POST"
                    style="display:inline-block; margin-left:8px;">
                    @csrf @method('DELETE')
                    <button class="btn" onclick="return confirm('Delete this unit?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div style="margin-top:12px;">{{ $units->links() }}</div>
@endsection