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
@include('about.about-section')
<!-- about Section End -->

<!-- Client Section Start -->
@include('about.client-section')
<!-- Client Section End -->

<!-- team section start -->
@include('about.team-section')
<!-- team section end -->

<!-- review Section Start -->
@include('about.review-section')
<!-- review Section End -->

<!-- Blog Section Start -->
@include('about.blog-section')
<!-- Blog Section End -->
@endsection