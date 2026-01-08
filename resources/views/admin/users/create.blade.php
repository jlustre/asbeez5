@extends('admin.layout')

@section('content')
<h1 style="margin:0 0 12px 0;">Create User</h1>

<form method="post" action="{{ route('admin.users.store') }}">
    @csrf
    @include('admin.users._form', ['user' => $user, 'countries' => $countries, 'timezones' => $timezones])
</form>
@endsection