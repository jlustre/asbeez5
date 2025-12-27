@php
$title = __('About Us');
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

<!-- about Section Start -->
@include('partials.about-section')
<!-- about Section End -->

<!-- Client Section Start -->
@include('partials.client-section')
<!-- Client Section End -->

<!-- team section start -->
@include('partials.team-section')
<!-- team section end -->

<!-- review Section Start -->
@include('partials.review-section')
<!-- review Section End -->

<!-- Blog Section Start -->
@include('partials.blog-section')
<!-- Blog Section End -->
@endsection