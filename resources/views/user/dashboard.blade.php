@php
$title = __('User Dashboard');
@endphp
@extends('layouts.main2')

@section('content')
@include('partials.breadcrumbs-section',
['breadcrumbs' => [
['label' => 'Home', 'url' => route('home')],
// ['label' => 'Company', 'url' => route('about')],
['label' => $title],
]
])
<!-- breadcrumb end -->

<!-- user dashboard section Start -->
@include('user.dashboard.user-dashboard-section')
<!-- user dashboard section End -->

<!-- user profile Start -->
@include('user.edit-user-profile')
<!-- user profile End -->

<!-- edit card Start -->
@include('user.edit-user-card')
<!-- edit card End -->

<!-- remove profile start -->
@include('user.remove-user-profile')
@include('user.remove-user-address')
<!-- remove profile end -->

<!-- edit profile Start -->
@include('user.edit-user-profile')
<!-- edit profile End -->

<!-- Add address modal box start -->
@include('user.add-user-address')
<!-- Add address modal box end -->
@endsection