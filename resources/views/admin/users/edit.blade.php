@extends('admin.layout')

@section('content')
<h1 style="margin:0 0 12px 0;">Edit User</h1>

<form method="post" action="{{ route('admin.users.update', $user) }}">
    @csrf
    @method('PUT')
    @include('admin.users._form', ['user' => $user, 'countries' => $countries, 'timezones' => $timezones])
</form>

@include('admin.users._addresses', ['user' => $user, 'countries' => $countries])
@endsection