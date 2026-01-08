@extends('admin.layout')

@section('content')
<h1 class="text-xl font-semibold mb-4">Add Vendor</h1>
<form method="POST" action="{{ route('admin.vendors.store') }}" class="space-y-4">
    @csrf
    @include('admin.vendors._form')
    <div>
        <button type="submit" class="btn">Save</button>
        <a href="{{ route('admin.vendors.index') }}" class="btn" style="background:#374151">Cancel</a>
    </div>
</form>
@endsection