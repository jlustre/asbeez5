@extends('admin.layout')

@section('content')
<h1 class="text-xl font-semibold mb-4">Edit Vendor #{{ $vendor->id }}</h1>
<form method="POST" action="{{ route('admin.vendors.update', $vendor) }}" class="space-y-4">
    @csrf
    @method('PUT')
    @include('admin.vendors._form')
    <div>
        <button type="submit" class="btn">Update</button>
        <a href="{{ route('admin.vendors.index') }}" class="btn" style="background:#374151">Cancel</a>
    </div>
</form>
@endsection