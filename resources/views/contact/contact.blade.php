@php
$title = __('Contact Us');
@endphp
@extends('layouts.main', ['title' => $title])

@section('content')

<!-- breadcrumb start -->
@include('partials.breadcrumbs-section',
['breadcrumbs' => [
['label' => 'Home', 'url' => route('home')],
// ['label' => 'Company', 'url' => route('about')],
['label' => $title],
]
])
<!-- breadcrumb end -->

<!-- Contact Box Section Start -->
@include('contact.contact-box-section')
<!-- Contact Box Section End -->

<!-- Map Section Start -->
@include('contact.map-section')
<!-- Map Section End -->

@endsection